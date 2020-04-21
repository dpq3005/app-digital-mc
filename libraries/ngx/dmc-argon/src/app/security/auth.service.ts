import {Injectable} from '@angular/core';
import {JwtHelperService} from "@auth0/angular-jwt";
import {Credentials, MerchantCredentials, SupervisorCredentials} from "./credentials";
import {HttpClient, HttpErrorResponse, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../services/config/config.service";
import {Configuration} from "../services/config/configuration";
import {Observable, throwError} from "rxjs";
import {catchError} from "rxjs/operators";
import {Merchant} from "../model/digital-medical-chit";

const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type': 'application/json'
  })
};

@Injectable({
  providedIn: 'any'
})
export class AuthService {
  config: Configuration;

  constructor(public jwtHelper: JwtHelperService, private http: HttpClient, private configService: ConfigService) {
    this.config = configService.getConfiguration();
  }

  public isAuthenticated(): boolean {
    const token = localStorage.getItem('token');
    // Check whether the token is expired and return
    // true or false
    try {
      if (this.jwtHelper.isTokenExpired(token)) {
        localStorage.setItem('token', null);
        return false;
      }
    } catch (ex) {
      console.log(ex);
      localStorage.removeItem('token');
      return false;
    }

    return true;
  }

  public authenticate(credentials: Credentials): Observable<any> {
    if (credentials instanceof SupervisorCredentials) {
      let keys = credentials.getKeys();
      let values = credentials.getValues();
      let loginUrl = this.config.getApiEndpoint('supervisor') + '/supervisor-token';

      return this.http.post(loginUrl, {
        'org-code': credentials.companyCode,
        'username': credentials.username,
        'password': credentials.password
      }, httpOptions).pipe(
        catchError(this.handleError)
      );
    }
    if (credentials instanceof MerchantCredentials) {

      let keys = credentials.getKeys();
      let values = credentials.getValues();
      let loginUrl = this.config.getApiEndpoint('supervisor') + '/merchant-pin-token';

      return this.http.post(loginUrl, {
        'uuid': credentials.uuid,
        'pin': credentials.pin
      }, httpOptions).pipe(
        catchError(this.handleError)
      );
    }
  }

  private handleError(error: HttpErrorResponse) {
    let errorCode = 500;
    if (error.error instanceof ErrorEvent) {
      // A client-side or network error occurred. Handle it accordingly.
      console.error('An error occurred:', error.error.message, error);
    } else {
      // The backend returned an unsuccessful response code.
      // The response body may contain clues as to what went wrong,
      console.error(
        `Backend returned code ${error.status}, ` +
        `body was: ${error.error}`);
      errorCode = error.status;
    }
    // return an observable with a user-facing error message
    return throwError(
      {code: errorCode, message: 'Invalid Credentials. Please try again!'});
  };
}
