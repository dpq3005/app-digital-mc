import {Component, OnInit, OnDestroy} from '@angular/core';
import {Router} from '@angular/router';
import {HttpService} from "../../services/http/http.service";
import {SwUpdate} from "@angular/service-worker";

@Component({
  selector: 'app-auth-layout',
  templateUrl: './auth-layout.component.html',
  styleUrls: ['./auth-layout.component.scss']
})
export class AuthLayoutComponent implements OnInit, OnDestroy {
  test: Date = new Date();
  public isCollapsed = true;

  isUpdateMsgVisible = false;

  constructor(private router: Router, private http: HttpService, private swUpdate: SwUpdate) {
  }

  ngOnInit() {
    var html = document.getElementsByTagName("html")[0];
    html.classList.add("auth-layout");
    var body = document.getElementsByTagName("body")[0];
    body.classList.add("bg-default");
    this.router.events.subscribe((event) => {
      this.isCollapsed = true;
    });

    this.swUpdate.available.subscribe(event => {
      this.isUpdateMsgVisible = true;
    })
  }

  ngOnDestroy() {
    var html = document.getElementsByTagName("html")[0];
    html.classList.remove("auth-layout");
    var body = document.getElementsByTagName("body")[0];
    body.classList.remove("bg-default");
  }


  closeUpdateMsg() {
    this.isUpdateMsgVisible = false;
  }

  reload() {
    this.swUpdate.activateUpdate().then(() => document.location.reload());
  }
}
