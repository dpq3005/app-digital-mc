export abstract class Credentials {
  protected type: CredentialsType;
  protected keys: string[];
  protected values: string[];

  static restoreFromJson(json: string): Credentials | null {
    let c = JSON.parse(json);
    switch (c.type) {
      case CredentialsType.SUPERVISOR:
        return Object.assign(new SupervisorCredentials(), c);
      case CredentialsType.MERCHANT:
        break;
    }
    return null;
  }

  abstract getKeys(): string[];

  abstract getValues(): string[];
}

export enum CredentialsType {
  SUPERVISOR = 'SUPERVISOR',
  MERCHANT = 'MERCHANT',
}

export class SupervisorCredentials extends Credentials {
  public companyCode: string;
  public username: string;
  public password: string;

  constructor() {
    super();
    this.type = CredentialsType.SUPERVISOR
  }

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
