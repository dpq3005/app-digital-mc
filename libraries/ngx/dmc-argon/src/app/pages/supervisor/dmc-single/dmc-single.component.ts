import {Component, OnInit} from '@angular/core';
import {DigitalMedicalChit, Merchant} from "../../../model/digital-medical-chit";
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

  dmc: DigitalMedicalChit;

  showMerchantSelect = false;

  constructor(private http: HttpService) {
  }

  trackByFn(item: Merchant) {
    return item.id;
  }

  ngOnInit() {
    this.dmc = new DigitalMedicalChit();
    this.dmc.initServices(this.http);
    // this.dmc.productId = '123';
    this.dmc.populateProductOptions();
  }

  onProductChange($event) {
    if (this.dmc.productId !== null) {
      this.dmc.populateMerchantOptions();
    }
  }

  createDmc() {
    console.log('create dmc', this.dmc);
    this.dmc.save();
  }

  handleNric() {
    this.loading = true;
    this.dmc.populateFromNric();
    this.loading = false;
    this.isNricReady = true;
  }

}
