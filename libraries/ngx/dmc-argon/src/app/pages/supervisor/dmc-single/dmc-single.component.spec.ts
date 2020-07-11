import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DmcSingleComponent } from './dmc-single.component';

describe('DmcSingleComponent', () => {
  let component: DmcSingleComponent;
  let fixture: ComponentFixture<DmcSingleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DmcSingleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DmcSingleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  // it('should create', () => {
  //   expect(component).toBeTruthy();
  // });
});
