import {Component, OnInit} from '@angular/core';
import {ConfigService} from "./services/config/config.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'argon-dashboard-angular';

  constructor(private config: ConfigService) {
  }

  ngOnInit(): void {
    let config = this.config.getConfiguration();
    console.log('test config', config,config.getApiEndpoint('global'));
  }
}
