import { TestBed } from '@angular/core/testing';

import { SupervisorAuthGuard } from './supervisor-auth.guard';

describe('SupervisorAuthGuard', () => {
  let guard: SupervisorAuthGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(SupervisorAuthGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
