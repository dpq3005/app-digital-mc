import {Component, OnInit} from '@angular/core';

@Component({
  selector: 'app-dmc-list',
  templateUrl: './dmc-list.component.html',
  styleUrls: ['./dmc-list.component.css']
})
export class DmcListComponent implements OnInit {
  loading = true;

  constructor() {
  }

  ngOnInit(): void {
    this.loading = false;
  }

  onFilterChange(filterName, $event) {
    console.log($event.target, $event.target.value, filterName)
    this.loading = true;
  }

}
