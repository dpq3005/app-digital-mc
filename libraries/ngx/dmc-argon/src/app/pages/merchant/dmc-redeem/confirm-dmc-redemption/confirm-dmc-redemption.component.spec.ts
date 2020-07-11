import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConfirmDmcRedemptionComponent } from './confirm-dmc-redemption.component';

describe('ConfirmDmcRedemptionComponent', () => {
  let component: ConfirmDmcRedemptionComponent;
  let fixture: ComponentFixture<ConfirmDmcRedemptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ConfirmDmcRedemptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ConfirmDmcRedemptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  // it('should create', () => {
  //   expect(component).toBeTruthy();
  // });
});
