import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InterimComponent } from './interim.component';

describe('InterimComponent', () => {
  let component: InterimComponent;
  let fixture: ComponentFixture<InterimComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InterimComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InterimComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
