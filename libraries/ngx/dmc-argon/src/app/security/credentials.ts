export abstract class Credentials {
  protected keys: string[];
  protected values: string[];

  abstract getKeys(): string[];

  abstract getValues(): string[];
}

export class SupervisorCredentials extends Credentials {
  public companyCode: string;
  public username: string;
  public password: string;

  getKeys(): string[] {
    this.keys = [];
    this.keys.push('org-code');
    this.keys.push('username');
    this.keys.push('password');
    return this.keys;
  }

  getValues(): string[] {
    this.values = [];
    this.values.push(this.companyCode);
    this.values.push(this.username);
    this.values.push(this.password);
    return this.values;
  }
}
