import {Injectable} from '@angular/core';
import {JwtHelperService} from "@auth0/angular-jwt";
import {Credentials, SupervisorCredentials} from "./credentials";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../services/config/config.service";
import {Configuration} from "../services/config/configuration";
import {Observable} from "rxjs";

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
    if (this.jwtHelper.isTokenExpired(token)) {
      localStorage.setItem('token', null);
      return false;
    }
    return true;
  }

  public authenticate(credentials: Credentials): Observable<any> {
    console.log('hey cren', credentials);
    if (credentials instanceof SupervisorCredentials) {
      console.log('hey SUPERCRE', credentials);
      let keys = credentials.getKeys();
      let values = credentials.getValues();
      let loginUrl = this.config.getApiEndpoint('supervisor') + '/supervisor-token';
      console.log('loginURL', loginUrl);
      return this.http.post(loginUrl, {
        'org-code': credentials.companyCode,
        'username': credentials.username,
        'password': credentials.password
      }, httpOptions);
    }
  }
}
