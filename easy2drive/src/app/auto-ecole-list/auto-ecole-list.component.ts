import { Component, OnInit } from '@angular/core';
import { AutoEcoleService, AutoEcole, ApiResponse } from '../auto-ecole.service';
import { Router } from '@angular/router';
import { catchError, finalize } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
  selector: 'app-auto-ecole-list',
  templateUrl: './auto-ecole-list.component.html',
  styleUrls: ['./auto-ecole-list.component.css'],
  standalone : false
})
export class AutoEcoleListComponent implements OnInit {
  autoEcoles: AutoEcole[] = [];
  isLoading = true;
  errorMessage = '';
  successMessage = '';

  constructor(
    private autoEcoleService: AutoEcoleService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadAutoEcoles();
  }

  loadAutoEcoles(): void {
    this.isLoading = true;
    this.errorMessage = '';
    this.successMessage = '';

    this.autoEcoleService.getAutoEcoles()
      .pipe(
        catchError((error) => {
          this.errorMessage = 'Erreur de connexion au serveur';
          console.error('Erreur:', error);
          return of(null); // Retourne un observable vide pour continuer le flux
        }),
        finalize(() => {
          this.isLoading = false;
        })
      )
      .subscribe({
        next: (response: ApiResponse<AutoEcole[]> | null) => {
          if (response?.success) {
            this.autoEcoles = response.data || [];
            this.successMessage = 'Chargement réussi';
          } else {
            this.errorMessage = response?.message || 'Erreur lors du chargement des données';
          }
        }
      });
  }

  addAutoEcole(): void {
    this.router.navigate(['/auto-ecoles/add']);
  }

  editAutoEcole(id: number): void {
    this.router.navigate(['/auto-ecoles/edit', id]);
  }

  deleteAutoEcole(id: number): void {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette auto-école ?')) {
      this.isLoading = true;
      this.errorMessage = '';
      
      this.autoEcoleService.deleteAutoEcole(id)
        .pipe(
          finalize(() => this.isLoading = false)
        )
        .subscribe({
          next: () => {
            this.successMessage = 'Auto-école supprimée avec succès';
            this.loadAutoEcoles();
          },
          error: (error) => {
            this.errorMessage = 'Échec de la suppression: ' + (error.error?.message || 'Erreur inconnue');
            console.error('Erreur:', error);
          }
        });
    } 
  }
}