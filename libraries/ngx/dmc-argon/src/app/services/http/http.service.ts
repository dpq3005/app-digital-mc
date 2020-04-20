import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../config/config.service";
import {Configuration} from "../config/configuration";
import {Observable} from "rxjs";
import {catchError} from "rxjs/operators";
import {isArray} from "util";

const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type': 'application/json'
  })
};

const httpGetOptions = {
  headers: new HttpHeaders({
    'Accept': 'application/json'
  })
};

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
    return this.http.post(url, postBody, httpOptions);
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
    return this.http.get(url, httpGetOptions);
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
    return this.http.delete(url, httpOptions);
  }
}

export enum Endpoint {
  GLOBAL = 'global',
  SUPERVISOR = 'supervisor',
  ENTITY = 'entity',
  PRODUCT = 'product'
}
