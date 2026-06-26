import { test, expect } from '@playwright/test';
import { registerUser } from './auth.helper';

test.describe('Author Form', () => {
    test.describe.configure({ mode: 'serial' });

    let page;

    test.beforeAll(async ({ browser }) => {
        test.setTimeout(120000);
        const context = await browser.newContext();
        page = await context.newPage();
        await registerUser(page);
    });

    test('shows author form on create page', async () => {
        await page.goto('/library/authors/create');
        await expect(page.getByText('New Author')).toBeVisible();
        await expect(page.getByLabel('First Name')).toBeVisible();
        await expect(page.getByLabel('Last Name')).toBeVisible();
        await expect(page.getByRole('button', { name: 'Create Author' })).toBeVisible();
    });

    test('can create an author', async () => {
        await page.goto('/library/authors/create');
        await page.getByLabel('First Name').fill('Jane');
        await page.getByLabel('Middle Name').fill('M');
        await page.getByLabel('Last Name').fill('Doe');
        await page.getByRole('button', { name: 'Create Author' }).click();

        await page.waitForURL(/\/library\/authors/);
        await expect(page.getByText('Jane M Doe').first()).toBeVisible();
    });
});
