import { Component, OnInit } from '@angular/core';
import {SwUpdate} from "@angular/service-worker";

@Component({
  selector: 'app-admin-layout',
  templateUrl: './admin-layout.component.html',
  styleUrls: ['./admin-layout.component.scss']
})
export class AdminLayoutComponent implements OnInit {

  isUpdateMsgVisible = false;

  constructor(private swUpdate: SwUpdate) {
    swUpdate.available.subscribe(event => {
      this.isUpdateMsgVisible = true;
    })
  }

  ngOnInit() {
  }

  closeUpdateMsg() {
    this.isUpdateMsgVisible = false;
  }

  reload() {
    this.swUpdate.activateUpdate().then(() => document.location.reload());
  }
}
