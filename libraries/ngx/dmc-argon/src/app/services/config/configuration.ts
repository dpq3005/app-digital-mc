export class Configuration {
  public api: ApiEndpoint;

  public getApiEndpoint(name: string): string {
    if (this.api.supervisor === null || typeof this.api.supervisor == "undefined") this.api.supervisor = this.api.global;
    return this.api[name];
  }
}

export class ApiEndpoint {
  version: number;
  global: string;
  supervisor: string;
}
