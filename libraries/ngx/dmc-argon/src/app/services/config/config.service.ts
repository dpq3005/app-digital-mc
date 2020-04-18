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

      if (config.api.supervisor === null || typeof config.api.supervisor == "undefined") config.api.supervisor = config.api.global;
      if (config.api.entity === null || typeof config.api.entity == "undefined") config.api.entity = config.api.global;
      if (config.api.product === null || typeof config.api.product == "undefined") config.api.product = config.api.global;

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
