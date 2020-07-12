export enum Role {
  SUPER_ADMIN = 'ROLE_SUPER_ADMIN',
  SUPERVISOR_DMC = 'ROLE_SUPERVISOR',
  SUPERVISOR_ALL = 'ROLE_SUPERVISOR_ALL',
  SUPERVISOR_TELEMED = 'ROLE_SUPERVISOR_TELEMED',
  MERCHANT_USER = 'ROLE_MERCHANT_USER',
}

export const RoleHierarchy = {
  ROLE_SUPERVISOR_ALL: [Role.SUPERVISOR_TELEMED, Role.SUPERVISOR_DMC],
  ROLE_SUPER_ADMIN: [Role.MERCHANT_USER, Role.SUPERVISOR_ALL],
}

export class RoleService {
  // static isGrantedAtRootLevel(uRole, gRole): boolean {
  //   return RoleHierarchy.hasOwnProperty(uRole) && gRole == uRole;
  // }
  static calculateRoleScore(role: Role, score): number {
    Object.keys(RoleHierarchy).forEach(function (iRole) {
      if (role.toString() == iRole.toString()) {
        score = RoleService.calculateRoleScore(role, ++score);
      }
    });
    return score;
  }

  static getRoleScore(role: Role): number {
    if (RoleService.hasOwnProperty(role.toString())) {
      return RoleService[role.toString()];
    }

    let score = 0;
    score = RoleService.calculateRoleScore(role, 0)
    RoleService[role.toString()] = score;
    return score;
  }
}
