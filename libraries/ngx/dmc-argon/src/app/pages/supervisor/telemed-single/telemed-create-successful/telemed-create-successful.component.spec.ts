import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TelemedCreateSuccessfulComponent } from './telemed-create-successful.component';

describe('TelemedCreateSuccessfulComponent', () => {
  let component: TelemedCreateSuccessfulComponent;
  let fixture: ComponentFixture<TelemedCreateSuccessfulComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TelemedCreateSuccessfulComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TelemedCreateSuccessfulComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
