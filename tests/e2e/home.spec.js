import { test, expect } from '@playwright/test';

test.describe('Home Page', () => {
    test('redirects unauthenticated user to login', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveURL(/\/login/);
    });

    test('shows login form with required fields', async ({ page }) => {
        await page.goto('/login');
        await expect(page.getByLabel('E-Mail Address')).toBeVisible();
        await expect(page.getByLabel('Password')).toBeVisible();
        await expect(page.getByRole('button', { name: /login/i })).toBeVisible();
    });
});
