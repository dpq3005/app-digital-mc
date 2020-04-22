import {Component, OnInit} from '@angular/core';
import {DigitalMedicalChit, DigitalMedicalChitCollection} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-dmc-list',
  templateUrl: './dmc-list.component.html',
  styleUrls: ['./dmc-list.component.css']
})
export class DmcListComponent implements OnInit {
  dmcCollection: DigitalMedicalChitCollection

  throttle = 300;
  scrollDistance = 1;
  scrollUpDistance = 2;
  direction = '';

  constructor(private http: HttpService, private router: Router) {
    this.dmcCollection = new DigitalMedicalChitCollection();
    this.dmcCollection.initServices(http);
    this.dmcCollection.loadItemsFromNextPage();
  }

  ngOnInit(): void {
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
