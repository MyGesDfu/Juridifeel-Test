import { TestBed } from '@angular/core/testing';

import { Cjn3Service } from './cjn3.service';

describe('Cjn3Service', () => {
  let service: Cjn3Service;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(Cjn3Service);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
