import {Injectable} from '@angular/core';
import {ApiEndpoint, Configuration} from "./configuration";
import {environment} from "../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class ConfigService {

  constructor() {
  }

  public initConfig() {
    let initialisationNecessary = false;
    let config = null;
    if (localStorage.getItem('config') === null) {
      initialisationNecessary = true;
    } else {
      let configuration = <Configuration>JSON.parse(localStorage.getItem('config'));
      if (!configuration) {
        initialisationNecessary = true;
      }
    }

    if (initialisationNecessary = true) {
      config = new Configuration();
      config.api = new ApiEndpoint();
      config.api.global = environment.apiGlobal;
      config.api.version = 1;
      localStorage.setItem('config', JSON.stringify(config));
    }
  }

  public getConfiguration() {
    this.initConfig();
    if (localStorage.getItem('config') === null) {
      return null;
    }
    let config = <Configuration>JSON.parse(localStorage.getItem('config'));
    return Object.assign(new Configuration(), config);
  }

}
