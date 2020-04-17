import {Endpoint, HttpService} from "../services/http/http.service";

export class DigitalMedicalChit {
  id: string;
  memberNric: string = null;
  memberName: string = null;
  productId = null;

  populateFromNric(http: HttpService) {
    // http.get(Endpoint.ENTITY, "benefit-providers/"++"/find-one-beneficiary-by-nric/G8899735U?page=");
  }
}
