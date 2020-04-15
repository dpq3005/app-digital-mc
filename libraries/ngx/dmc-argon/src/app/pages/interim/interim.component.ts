import { Component, OnInit } from '@angular/core';
import {Router} from "@angular/router";

@Component({
  selector: 'app-interim',
  templateUrl: './interim.component.html',
  styleUrls: ['./interim.component.css']
})
export class InterimComponent implements OnInit {

  constructor(public router: Router) { }

  ngOnInit(): void {
    this.router.navigate(['supervisor','dmc','list'])
  }

}
