import {Component, HostListener, OnInit} from '@angular/core';
import {SwUpdate} from "@angular/service-worker";

@Component({
  selector: 'app-admin-layout',
  templateUrl: './admin-layout.component.html',
  styleUrls: ['./admin-layout.component.scss']
})
export class AdminLayoutComponent implements OnInit {

  isUpdateMsgVisible = false;
  deferredPrompt: any;
  isA2HSButtonVisible = false;

  @HostListener('window:beforeinstallprompt', ['$event'])
  onBeforeInstallPrompt(e) {
    console.log(e);
    e.preventDefault;
    this.deferredPrompt = e;
    this.isA2HSButtonVisible = true;
  }

  constructor(private swUpdate: SwUpdate) {

  }

  ngOnInit() {
    this.swUpdate.available.subscribe(event => {
      this.isUpdateMsgVisible = true;
    })
  }

  closeA2HSMessage() {
    this.isA2HSButtonVisible = false;
  }

  closeUpdateMsg() {
    this.isUpdateMsgVisible = false;
  }

  reload() {
    this.swUpdate.activateUpdate().then(() => document.location.reload());
  }

  add2HS() {
    this.isA2HSButtonVisible = false;
    this.deferredPrompt.prompt();
    this.deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('User accepted A2HS prompt');
        localStorage.setItem('a2hs', JSON.stringify(choiceResult));
      } else {
        console.log('User refused A2HS prompt');
        localStorage.setItem('a2hs', JSON.stringify(choiceResult));
      }
      this.deferredPrompt = null;
    });
  }
}
