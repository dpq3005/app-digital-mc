import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../config/config.service";
import {Configuration} from "../config/configuration";
import {Observable} from "rxjs";
import {catchError} from "rxjs/operators";

@Injectable({
  providedIn: 'any'
})
export class HttpService {
  config: Configuration;

  constructor(private http: HttpClient, private configService: ConfigService) {
    this.config = configService.getConfiguration();
  }

  public post(endpoint: Endpoint, pathSegments: [string], postBody: any, headers?): Observable<any> {
    let url = null;
    let path = null;
    if (Array.isArray(pathSegments)) {
      if (pathSegments.length > 0) {
        path = pathSegments.pop();
      }
    }

    if (path == null || typeof path == "undefined") {
      url = this.config.getApiEndpoint(endpoint);
    } else {
      url = this.config.getApiEndpoint(endpoint) + '/' + path;
    }
    return this.http.post(url, postBody, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      })
    });
  }

  public get(endpoint: Endpoint, pathSegments: [string], headers?): Observable<any> {
    let url = null;
    let path = null;
    if (Array.isArray(pathSegments)) {
      if (pathSegments.length > 0) {
        path = pathSegments.pop();
      }
    }

    if (path == null || typeof path == "undefined") {
      url = this.config.getApiEndpoint(endpoint);
    } else {
      url = this.config.getApiEndpoint(endpoint) + '/' + path;
    }

    return this.http.get(url, {
      headers: new HttpHeaders({
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      })
    });
  }

  public delete(endpoint: Endpoint, pathSegments: [string], headers?): Observable<any> {
    let url = null;
    let path = null;
    if (Array.isArray(pathSegments)) {
      if (pathSegments.length > 0) {
        path = pathSegments.pop();
      }
    }

    if (path == null || typeof path == "undefined") {
      url = this.config.getApiEndpoint(endpoint);
    } else {
      url = this.config.getApiEndpoint(endpoint) + '/' + path;
    }
    return this.http.delete(url, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      })
    });
  }
}

export enum Endpoint {
  GLOBAL = 'global',
  SUPERVISOR = 'supervisor',
  ENTITY = 'entity',
  PRODUCT = 'product'
}
