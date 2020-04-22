import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DmcRedeemComponent } from './dmc-redeem.component';

describe('DmcRedeemComponent', () => {
  let component: DmcRedeemComponent;
  let fixture: ComponentFixture<DmcRedeemComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DmcRedeemComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DmcRedeemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
