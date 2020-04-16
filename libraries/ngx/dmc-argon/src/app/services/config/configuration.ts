export class Configuration {
  api: ApiEndpoint;

  public getApiEndpoint(name: string) {
    return this.api[name];
  }
}

export class ApiEndpoint {
  version: number;
  global: string;
  dmc: string;
}
