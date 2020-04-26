import { TestBed } from '@angular/core/testing';

import { MerchantAuthGuard } from './merchant-auth.guard';

describe('MerchantAuthGuard', () => {
  let guard: MerchantAuthGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(MerchantAuthGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
