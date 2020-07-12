import {Injectable} from '@angular/core';
import {JwtHelperService} from "@auth0/angular-jwt";
import {Credentials, MerchantCredentials, SupervisorCredentials} from "./credentials";
import {HttpClient, HttpErrorResponse, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../services/config/config.service";
import {Configuration} from "../services/config/configuration";
import {Observable, throwError} from "rxjs";
import {catchError} from "rxjs/operators";
import {Role, RoleHierarchy, RoleService} from "./role.service";
import {createBrowserLoggingCallback} from "@angular-devkit/build-angular/src/browser";

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

  isSupervisor: boolean = null; // For BC
  isSupervisorDmc: boolean = null;
  isSupervisorTelemed: boolean = null;
  isMerchantUser: boolean = null;

  public logout() {
    localStorage.clear();
    this.isSupervisorDmc = this.isMerchantUser = this.isSupervisor = this.isSupervisorTelemed = null;
  }

  public convertRoleToPropertyString(gRole: String): String {
    let gRoleProperty = gRole.substr("ROLE_".length);
    let gRolePropertyParts = gRoleProperty.split("_");
    gRoleProperty = '';
    gRolePropertyParts.forEach(function (part) {
      gRoleProperty += part.toUpperCase().charAt(0) + part.toLowerCase().substr(1);
    })
    return 'is' + gRoleProperty;
  }

  public isGranted(gRole: Role) {
    let gRolePropertyString = this.convertRoleToPropertyString(gRole.toString());
    let gRoleProperty = gRolePropertyString.toString();

    if (this[gRoleProperty] != null) {
      console.log('cached role ' + gRoleProperty + ' --- ' + this[gRoleProperty])
      return this[gRoleProperty];
    }

    let user = this.getUser();
    // if (gRole === Role.SUPERVISOR_DMC) {
    //   if (this.isSupervisorDmc !== null) {
    //     return this.isSupervisorDmc;
    //   }
    // }

    let authService = this;
    user.roles.every(function (uRole) {
      if (uRole.toString() == gRole.toString()) {
        authService[gRoleProperty] = true;
        return false;
      }
      let uRoleScore = RoleService.getRoleScore(uRole);
      let gRoleScore = RoleService.getRoleScore(gRole);
      if (uRoleScore > gRoleScore) {
        authService[gRoleProperty] = true;
        return false;
      }

      return true;
    });

    // if (gRole === Role.MERCHANT_USER) {
    //   if (this.isMerchantUser !== null) {
    //     return this.isMerchantUser;
    //   }
    //   for (let i = 0; i < user.roles.length; i++) {
    //     let r = user.roles[i];
    //     if (r === Role.MERCHANT_USER) {
    //       this.isMerchantUser = true;
    //       return true;
    //     }
    //   }
    //   this.isMerchantUser = false;
    // }
    console.log("WHY ", this[gRoleProperty], gRole)
    if (this[gRoleProperty] == null) {
      this[gRoleProperty] = false;
    }
    return this[gRoleProperty];
  }

  public getUser() {
    const token = localStorage.getItem('token');
    const jwt = this.jwtHelper.decodeToken(token);
    return jwt;
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

  public reAuthenticate(): Observable<any> {
    console.log('Re-authenticating');
    let c = localStorage.getItem('credentials');
    if (c === null) {
      return throwError(
        {code: 401, message: 'Credentials not found. Please login!'});
    }
    let credentials = Credentials.restoreFromJson(c);
    return this.authenticate(credentials);
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
