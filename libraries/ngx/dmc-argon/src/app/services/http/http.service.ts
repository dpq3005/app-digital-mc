import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../config/config.service";
import {Configuration} from "../config/configuration";
import {Observable} from "rxjs";

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

  public get(endpoint: Endpoint, path, headers?): Observable<any> {
    let url = null;
    if (path == null || typeof path == "undefined") {
      url = this.config.getApiEndpoint(endpoint);
    } else {
      url = this.config.getApiEndpoint(endpoint) + '/' + path;
    }
    return this.http.get(url, httpGetOptions);
  }
}

export enum Endpoint {
  SUPERVISOR = 'supervisor',
  ENTITY = 'entity'
}
