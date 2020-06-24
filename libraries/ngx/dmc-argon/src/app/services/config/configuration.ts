export class Configuration {
  public api: ApiEndpoint;

  public getApiEndpoint(name: string): string {
    return this.api[name];
  }
}

export class ApiEndpoint {
  version: number;
  global: string;
  supervisor: string;
  entity: string;
  product: string;
}
