import { Component, OnInit } from '@angular/core';
import { SirenService } from '../../services/siren.service';
import { NafService } from '../../services/naf.service';
import { SiretService } from '../../services/siret.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-etablissement',
  standalone: true,
  imports: [CommonModule, FormsModule],  
  templateUrl: './etablissement.component.html',
  styleUrls: ['./etablissement.component.scss']
})

export class EtablissementComponent implements OnInit {
  siren: string = '';
  data: any = null;
  error: string = '';
  nafDetails: string = '';

  constructor(private sirenService: SirenService, private nafService: NafService, private siretService: SiretService) {}

  ngOnInit(): void {}

  onSearch(): void {
    if (this.siren.trim().length === 9) {
      this.sirenService.getEtablissementBySiren(this.siren).subscribe({
        next: (response) => {
          console.log('Données récupérées:', response);
          this.data = response;
          this.error = '';
          function toTitleCase(str: string): string {
            return str
              .toLowerCase()
              .replace(/\b\w/g, char => char.toUpperCase());
          }

          this.data.nom = toTitleCase(this.data.nom);

          // Récupérer le NIC et construire le SIRET
          const nic = this.data.nicSiege;
          const siret = `${this.siren}${nic}`;
          
          // Recherche par SIRET pour obtenir des informations supplémentaires
          this.getSiretDetails(siret);

          // Récupérer les détails du code NAF si présent
          if (this.data.codeNafApe) {
            this.getNafDetails(this.data.codeNafApe);
          }
        },
        error: (err) => {
          console.error('Erreur lors de la récupération des données:', err);
          this.data = null;
          this.error = err.status === 404
            ? 'Aucune donnée trouvée pour ce SIREN.'
            : 'Impossible de récupérer les données. Vérifiez le SIREN et réessayez.';
        }
      });
    } else {
      this.error = 'Le numéro de SIREN doit contenir 9 chiffres.';
      this.data = null;
    }
  }

  getNafDetails(code: string): void {
    this.nafService.getNafDetails(code).subscribe({
      next: (response) => {
        this.nafDetails = response.intitule || 'Détails introuvables';
      },
      error: (err) => {
        console.error('Erreur récupération NAF', err);
        this.nafDetails = 'Erreur lors de la récupération des détails du NAF';
      }
    });
  }

  getSiretDetails(siret: string): void {
    this.siretService.getSiretDetails(siret).subscribe({
      next: (response) => {
        console.log('Données récupérées pour SIRET:', response);
        this.data.siretDetails = response;
        this.error = '';
        function toTitleCase(str: string): string {
          return str
            .toLowerCase()
            .replace(/\b\w/g, char => char.toUpperCase());
        }
        if (this.data.siretDetails.siegeSocial && this.data.siretDetails) {
          const adresseParts = [
            this.data.siretDetails.numeroVoieEtablissement,
            this.data.siretDetails.typeVoieEtablissement.toLowerCase(),
            this.data.siretDetails.libelleVoieEtablissement.toLowerCase(),
            this.data.siretDetails.codePostalEtablissement,
            toTitleCase(this.data.siretDetails.libelleCommuneEtablissement),
          ];
          this.data.siretDetails.adresse = adresseParts.filter(part => part).join(' ') || '';
        } else {
          this.data.siretDetails = this.data.siretDetails || {};
          this.data.siretDetails.adresse = "Non renseigné";
        }
      },
      error: (err) => {
        console.error('Erreur lors de la récupération des données pour le SIRET:', err);
        this.data.siretDetails = null;
        this.error = err.status === 404
          ? 'Aucune donnée trouvée pour ce SIRET.'
          : 'Impossible de récupérer les données. Vérifiez le SIRET et réessayez.';
      }
    });
  }
}
