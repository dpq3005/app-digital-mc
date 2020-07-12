// https://material.angular.io/cdk/scrolling/overview
import {Component, OnInit} from '@angular/core';
import {ModalDismissReasons, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {DigitalMedicalChit, DigitalMedicalChitCollection} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";

@Component({
  selector: 'app-telemed-list',
  templateUrl: './telemed-list.component.html',
  styleUrls: ['./telemed-list.component.css']
})
export class TelemedListComponent implements OnInit {
  closeResult = '';
  telemedCollection: DigitalMedicalChitCollection;
  items = Array.from({length: 100000}).map((_, i) => `Item #${i}`);

  constructor(private modalService: NgbModal, private http: HttpService) {
    this.telemedCollection = new DigitalMedicalChitCollection(true);
  }

  ngOnInit(): void {
    this.telemedCollection.initServices(this.http);
    this.telemedCollection.loadItemsFromNextPage();
  }

  throttle = 300;
  scrollDistance = 1;
  scrollUpDistance = 2;
  direction = '';

  onScrollDown($event) {
    console.log('scrolled down!!', $event);
    this.telemedCollection.loadItemsFromNextPage();
    this.direction = 'down'
  }

  tracktelemedCollection(index: number, item: DigitalMedicalChit): string {
    return item.id;
  }

  confirmDelete(content, telemed: DigitalMedicalChit) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
      if (result === 'YES') {
        console.log('let s detete');
        this.telemedCollection.deleteItem(telemed);
      }
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
