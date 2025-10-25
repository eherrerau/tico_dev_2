<?php

declare(strict_types=1);

use Tico\Services\AuthService;
use Tico\Models\TestUser;

describe('AuthService', function () {
    beforeEach(function () {
        $this->authService = new AuthService();
    });

    it('can be instantiated', function () {
        expect($this->authService)->toBeInstanceOf(AuthService::class);
    });

    it('generates CSRF tokens', function () {
        $token = $this->authService->getCsrfToken();

        expect($token)
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('validates CSRF tokens correctly', function () {
        $token = $this->authService->getCsrfToken();

        expect($this->authService->validateCsrfToken($token))->toBeTrue();
        expect($this->authService->validateCsrfToken('invalid-token'))->toBeFalse();
    });

    it('can logout users', function () {
        $this->authService->logout();

        expect($this->authService->isAuthenticated())->toBeFalse();
    });
});

describe('TestUser Model', function () {
    beforeEach(function () {
        $this->userModel = new TestUser();
    });

    it('can be instantiated', function () {
        expect($this->userModel)->toBeInstanceOf(TestUser::class);
    });

    it('can get teams', function () {
        $teams = $this->userModel->getTeams();

        expect($teams)
            ->toBeArray()
            ->not->toBeEmpty();
    });

    it('can find users by username', function () {
        $user = $this->userModel->findByUsername('admin');

        expect($user)
            ->toBeArray()
            ->toHaveKey('usrName', 'admin');
    });
});
