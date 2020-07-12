import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TelemedSingleComponent } from './telemed-single.component';

describe('DmcSingleComponent', () => {
  let component: TelemedSingleComponent;
  let fixture: ComponentFixture<TelemedSingleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TelemedSingleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TelemedSingleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  // it('should create', () => {
  //   expect(component).toBeTruthy();
  // });
});
