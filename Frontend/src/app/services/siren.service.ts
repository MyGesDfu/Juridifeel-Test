import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class SirenService {
  private apiUrl = 'https://api.insee.fr/entreprises/sirene/V3.11/siren'; 

  constructor(private http: HttpClient) {}

  getEtablissement(siren: string): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Authorization': `Bearer ${environment.apiToken}`, 
    });

    return this.http.get<any>(`${this.apiUrl}/${siren}`, { headers }).pipe(
      map(data => {
        if (!data || !data.uniteLegale) {
          throw new Error('Données invalides');
        }

        const periode = data.uniteLegale.periodesUniteLegale?.[0] || {};

        return {
          nom: periode.denominationUniteLegale ?? 'Non renseigné',
          formeSociale: data.uniteLegale.categorieJuridiqueUniteLegale ?? 'Non renseigné',
          siegeSocial: data.uniteLegale.nicSiegeUniteLegale ?? 'Non renseigné',
          capitalSocial: 'Non renseigné', // L'API INSEE ne fournit pas cette info
          numeroSiren: data.uniteLegale.siren ?? 'Non renseigné',
          numeroSiret: `${data.uniteLegale.siren}${data.uniteLegale.nicSiegeUniteLegale ?? ''}`,
          numeroRCS: `RCS ${periode.denominationUniteLegale ?? 'Non renseigné'}`,
          immatriculation: data.uniteLegale.dateCreationUniteLegale ?? 'Non renseigné',
          clotureExerciceSocial: 'Non disponible via l\'INSEE', // Besoin d'une autre source
          numeroTVA: `FR${data.uniteLegale.siren ?? 'Non renseigné'}`,
          codeNafApe: periode.activitePrincipaleUniteLegale ?? 'Non renseigné',
          activitePrincipale: periode.activitePrincipaleUniteLegale ?? 'Non renseigné',
        };
      })
    );
  }
}
