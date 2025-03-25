import { Component, OnInit } from '@angular/core';
import { StudentService } from '../student.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-test-list',
  templateUrl: './test-list.component.html',
  styleUrls: ['./test-list.component.css'],
  standalone: false
})
export class TestListComponent implements OnInit {
  tests: any[] = [];
  isLoading = true;

  constructor(
    private studentService: StudentService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadTests();
  }

  loadTests(): void {
    this.isLoading = true;
    this.studentService.getTests().subscribe({
      next: (response) => {
        if (response.success && response.tests) {
          this.tests = response.tests;
        }
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erreur lors du chargement', error);
        this.isLoading = false;
      }
    });
  }

  deleteTest(id: number): void {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce test ?')) {
      this.studentService.deleteTest(id).subscribe({
        next: () => {
          this.loadTests();
        },
        error: (error) => {
          console.error('Erreur lors de la suppression', error);
          alert('Erreur lors de la suppression');
        }
      });
    }
  }

  editTest(id: number): void {
    this.router.navigate(['/edit-test', id]);
  }

  addTest(): void {
    this.router.navigate(['/add-test']);
  }
}