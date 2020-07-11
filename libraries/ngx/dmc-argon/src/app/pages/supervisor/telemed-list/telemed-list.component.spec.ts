import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import {TelemedListComponent} from "./telemed-list.component";

describe('TelemedListComponent', () => {
  let component: TelemedListComponent;
  let fixture: ComponentFixture<TelemedListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TelemedListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TelemedListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  // it('should create', () => {
  //   expect(component).toBeTruthy();
  // });
});
