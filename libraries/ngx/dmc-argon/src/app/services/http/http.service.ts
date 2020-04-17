import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {ConfigService} from "../config/config.service";
import {Configuration} from "../config/configuration";

const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type': 'application/json'
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

  public get(endpoint: Endpoint, path, headers) {
    let url = null;
    if (path == null || typeof path == "undefined") {
      url = this.config.getApiEndpoint(endpoint);
    } else {
      url = this.config.getApiEndpoint(endpoint) + '/' + path;
    }
    return this.http.get(url, httpOptions);
  }
}

export enum Endpoint {
  SUPERVISOR = 'supervisor',
  ENTITY = 'entity'
}
