import { Component, OnInit } from '@angular/core';
import {Observable} from "rxjs";

@Component({
  selector: 'app-dmc-single',
  templateUrl: './dmc-single.component.html',
  styleUrls: ['./dmc-single.component.css']
})
export class DmcSingleComponent implements OnInit {

  people$: any[];
  selectedPersonId = '123';

  constructor() {
  }

  ngOnInit() {
    this.people$ = [
      {id: '123', ok:'ok'},
      {id: '456', ok:'456'}
      ];
  }

}
