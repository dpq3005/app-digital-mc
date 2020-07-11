import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DmcListComponent } from './dmc-list.component';

describe('DmcListComponent', () => {
  let component: DmcListComponent;
  let fixture: ComponentFixture<DmcListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DmcListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DmcListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  // it('should create', () => {
  //   expect(component).toBeTruthy();
  // });
});
