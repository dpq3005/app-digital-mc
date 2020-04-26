import {Component, OnInit} from '@angular/core';
import {DigitalMedicalChit, DigitalMedicalChitCollection} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";
import {Router} from "@angular/router";
import {Merchant} from "../../../model/merchant";
import {AuthService} from "../../../security/auth.service";

@Component({
  selector: 'app-dmc-list',
  templateUrl: './dmc-list.component.html',
  styleUrls: ['./dmc-list.component.css']
})
export class DmcListComponent implements OnInit {
  dmcCollection: DigitalMedicalChitCollection

  loggedInMerchant: Merchant;

  throttle = 300;
  scrollDistance = 1;
  scrollUpDistance = 2;
  direction = '';

  constructor(private http: HttpService, private router: Router) {
    this.dmcCollection = new DigitalMedicalChitCollection();
    this.dmcCollection.initServices(http);
    this.dmcCollection.loadItemsFromNextPage();
    this.loggedInMerchant = new Merchant();
    this.loggedInMerchant.initServices(http);
  }

  ngOnInit(): void {
    this.loggedInMerchant.id = localStorage.getItem('merchantUuid');
    this.loggedInMerchant.load();
  }

  onFilterChange(filterName, $event) {
    console.log($event.target, $event.target.value, filterName)
    this.dmcCollection.loadItemsFromFirstPage();
  }

  redeem(dmc: DigitalMedicalChit, $event) {
    this.router.navigate(['merchant', 'dmc', dmc.id, 'redeem']);
  }

  onScrollDown(ev) {
    console.log('scrolled down!!', ev);
    this.dmcCollection.loadItemsFromNextPage();
    this.direction = 'down'
  }
}
