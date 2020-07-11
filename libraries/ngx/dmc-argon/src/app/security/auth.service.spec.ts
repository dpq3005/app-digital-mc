import { TestBed } from '@angular/core/testing';

import {AuthService, Role} from './auth.service';

describe('AuthService', () => {
  let service: AuthService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(AuthService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('convertRoleToPropertyString should return cached Property String', () => {
    console.log("HEY")
    expect(service.convertRoleToPropertyString(Role.SUPERVISOR_DMC)).toBe("SupervisorDmcc");
  });

});

