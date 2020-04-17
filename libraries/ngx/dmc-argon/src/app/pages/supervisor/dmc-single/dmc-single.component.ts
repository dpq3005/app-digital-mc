import {Component, OnInit} from '@angular/core';
import {DigitalMedicalChit} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";

@Component({
  selector: 'app-dmc-single',
  templateUrl: './dmc-single.component.html',
  styleUrls: ['./dmc-single.component.css']
})
export class DmcSingleComponent implements OnInit {

  isNricReady: boolean = false;
  loading: boolean = false;

  products: any[];
  merchants: any[];

  selectAllMerchants = 'ALL';
  selectedCemtres = [];

  dmc: DigitalMedicalChit;

  showMerchantSelect = false;

  constructor(private http: HttpService) {
  }

  ngOnInit() {
    this.dmc = new DigitalMedicalChit();
    this.dmc.productId = '123';

    this.products = [
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

  handleNric() {
    this.loading = true;
    alert(this.dmc.memberNric);
    this.dmc.populateFromNric(this.http);
    this.loading = false;
    this.isNricReady = true;
  }

}
