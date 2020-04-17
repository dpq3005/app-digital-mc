import {Endpoint, HttpService} from "../services/http/http.service";
import {Observable} from "rxjs";

export class DigitalMedicalChit {
  id: string;
  memberNric: string = null;
  memberName: string = null;
  productId = null;

  populateFromNric(http: HttpService) {
    // /benefit-providers/15e6f99ba1de1c/find-one-beneficiary-by-nric/13/lalana(NAING)042215
    let uuid = localStorage.getItem('benefitProviderUuid');
    let nric = this.memberNric;
    try {
      http.get(Endpoint.ENTITY, "benefit-providers/" + uuid + "/find-one-beneficiary-by-nric/" + nric + "?page=1").subscribe((res: any) => {
        console.log(res);
        this.memberName = res.name;
      });
    } catch (err) {

    }
  }
}
