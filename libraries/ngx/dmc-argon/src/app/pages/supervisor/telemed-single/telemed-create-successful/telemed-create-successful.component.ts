import { Component, OnInit } from '@angular/core';
import {DigitalMedicalChit} from "../../../../model/digital-medical-chit";

@Component({
  selector: 'app-telemed-create-successful',
  templateUrl: './telemed-create-successful.component.html',
  styleUrls: ['./telemed-create-successful.component.css']
})
export class TelemedCreateSuccessfulComponent implements OnInit {

  beneficiaryName: string;

  constructor() {
    this.beneficiaryName = localStorage.getItem('beneficiaryName');
  }

  ngOnInit(): void {
  }

}
