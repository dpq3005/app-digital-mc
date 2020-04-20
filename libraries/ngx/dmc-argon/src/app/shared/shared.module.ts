import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {PinDigitDirective} from "../directives/pin-digit/pin-digit.directive";

@NgModule({
  declarations: [PinDigitDirective],
  imports: [
    CommonModule
  ],
  exports: [
    PinDigitDirective
  ]
})
export class SharedModule { }
