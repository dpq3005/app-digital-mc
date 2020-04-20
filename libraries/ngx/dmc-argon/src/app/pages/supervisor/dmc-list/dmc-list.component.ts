// https://material.angular.io/cdk/scrolling/overview

import {Component, OnInit} from '@angular/core';
import {ModalDismissReasons, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {DigitalMedicalChitCollection} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";

@Component({
  selector: 'app-dmc-list',
  templateUrl: './dmc-list.component.html',
  styleUrls: ['./dmc-list.component.css']
})
export class DmcListComponent implements OnInit {
  closeResult = '';
  private dmcCollection: DigitalMedicalChitCollection;
  items = Array.from({length: 100000}).map((_, i) => `Item #${i}`);

  constructor(private modalService: NgbModal, private http: HttpService) {
    this.dmcCollection = new DigitalMedicalChitCollection();
  }

  ngOnInit(): void {
    this.dmcCollection.initServices(this.http);
  }

  sum = 100;
  throttle = 300;
  scrollDistance = 1;
  scrollUpDistance = 2;
  direction = '';

  onScrollDown(ev) {
    console.log('scrolled down!!', ev);
    this.dmcCollection.loadItemsFromNextPage();
    this.direction = 'down'
  }

  openModal(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
      console.log('this.closeResult', this.closeResult);
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      console.log('this.closeResult', this.closeResult);
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }

}
