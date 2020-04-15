import {Directive, ElementRef, HostListener, Input} from '@angular/core';

@Directive({
  selector: '[appPinDigit]'
})
export class PinDigitDirective {

  private el: ElementRef;
  @Input() onReturn: string;

  constructor(private _el: ElementRef) {
    this.el = this._el;
  }

  getSrcElement(e) {
    let srcElement = e.srcElement;
    if (srcElement == null) {
      srcElement = e.target;
    }
    return srcElement;
  }

  getKeyCode(e) {
    // Handle the event with KeyboardEvent.key and set handled true.
    let keyCode = e.key;
    if (keyCode == undefined) {
      // Handle the event with KeyboardEvent.keyCode and set handled true.
      keyCode = e.keyCode;
    } else {
      if (keyCode === 'ArrowLeft') {
        return 37;
      }
      if (keyCode === 'ArrowRight') {
        return 39;
      }
      if (keyCode === 'Backspace') {
        return 8;
      }
      if (keyCode === 'Delete') {
        return 46;
      }
      return keyCode.charCodeAt(0);
    }

    if (keyCode == undefined) {
      // Handle the event with KeyboardEvent.which and set handled true.
      keyCode = e.which;
    }

    return keyCode;
  }

  @HostListener('keydown', ['$event']) onKeyDown(e) {
    let srcElement = this.getSrcElement(e);
    let keyCode = this.getKeyCode(e);

    if (keyCode == undefined) {
      e.preventDefault();
      return false;
    }

    if (keyCode === 13) {
      e.preventDefault();
      return;
    }

    if (keyCode === 37) {
      if (srcElement.previousElementSibling) {
        srcElement.previousElementSibling.focus();
      }
      return;
    } else if (keyCode === 39) {
      if (srcElement.nextElementSibling) {
        srcElement.nextElementSibling.focus();
      }
      return;
    }

    if (srcElement.value !== '') {
      srcElement.value = '';
    }
  }

  @HostListener('keyup', ['$event']) onKeyUp(e) {

    let srcElement = this.getSrcElement(e);
    // Handle the event with KeyboardEvent.key and set handled true.
    let keyCode = this.getKeyCode(e);
    console.log('keycode ', keyCode);

    if (keyCode == undefined) {
      e.preventDefault();
      return false;
    }

    if (keyCode === 13) {
      e.preventDefault();
      return;
    }

    const isNumericKey = keyCode === 229 || (keyCode >= 48 && keyCode <= 57); // || (e.which >= 48 && e.which <= 57)

    if (keyCode === 8) {
      if (srcElement.previousElementSibling) {
        srcElement.previousElementSibling.focus();
      } else {
        console.log('close keyboard');
      }
    } else if (isNumericKey || (keyCode >= 96 && keyCode <= 105)) { // (e.which >= 96 && e.which <= 105)
      // 0-9 only
      if (srcElement.nextElementSibling) {
        console.log('nextElsib', srcElement.nextElementSibling)
        srcElement.nextElementSibling.focus();
      } else {
        console.log('close keyboard');
      }
    }
  }
}
