export enum Role {
  SUPER_ADMIN = 'ROLE_SUPER_ADMIN',
  SUPERVISOR_DMC = 'ROLE_SUPERVISOR',
  SUPERVISOR_ALL = 'ROLE_SUPERVISOR_ALL',
  SUPERVISOR_TELEMED = 'ROLE_SUPERVISOR_TELEMED',
  MERCHANT_USER = 'ROLE_MERCHANT_USER',
}

export const RoleHierarchy = {
  SUPERVISOR_ALL: [Role.SUPERVISOR_TELEMED, Role.SUPERVISOR_DMC],
  ROLE_SUPER_ADMIN: [Role.MERCHANT_USER, Role.SUPERVISOR_ALL],
}

export class RoleService {
  static isGrantedAtRootLevel(uRole, gRole): boolean {
    return RoleHierarchy.hasOwnProperty(uRole) && gRole == uRole;
  }

  static isGrantedAtChildren(uRole, gRole, childLevel: Role[]): boolean {
    let granted = false;
    childLevel.every(function (roleLevel) {
      if (RoleService.isGrantedAtLevel(uRole, gRole, roleLevel)) {
        granted = true;
        return false;
      }
    });
    return granted;
  }

  static isGrantedAtLevel(uRole, gRole, roleLevel: Role): boolean {
    if (!RoleHierarchy.hasOwnProperty(roleLevel)) {
      return gRole == uRole;
    }

    return RoleService.isGrantedAtChildren(uRole, gRole, RoleHierarchy[roleLevel]);
  }
}
