import {Component, OnInit} from '@angular/core';
import {Observable} from "rxjs";
import {DigitalMedicalChit} from "../../../model/digital-medical-chit";

@Component({
  selector: 'app-dmc-single',
  templateUrl: './dmc-single.component.html',
  styleUrls: ['./dmc-single.component.css']
})
export class DmcSingleComponent implements OnInit {

  people$: any[];
  merchants: any[];

  selectedPersonId = '123';
  selectAllMerchants = 'ALL';
  selectedCemtres = [];

  dmc: DigitalMedicalChit;

  showMerchantSelect = false;

  constructor() {
  }

  ngOnInit() {
    this.dmc = new DigitalMedicalChit();

    this.people$ = [
      {id: '123', name: 'Outpatient Care Plan A'},
      {id: '456', name: 'Outpatient Care Plan B'}
    ];
    this.merchants = [
      {id: '123', name: 'Tuas South Clinic'},
      {id: '456', name: 'Some Clinic'}
    ];
  }

  validate() {

  }

}
