import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SirenService {

  private apiUrl = 'http://localhost:9001/api/etablissement';

  constructor(private http: HttpClient) {}

  getEtablissementBySiren(siren: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${siren}`);
  }
}
