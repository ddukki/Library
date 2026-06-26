import { test, expect } from '@playwright/test';
import { registerUser } from './auth.helper';

test.describe('Book Form', () => {
    test.describe.configure({ mode: 'serial' });

    let page;

    test.beforeAll(async ({ browser }) => {
        test.setTimeout(120000);
        const context = await browser.newContext();
        page = await context.newPage();
        await registerUser(page);
    });

    test('shows book form on create page', async () => {
        await page.goto('/library/books/create');
        await expect(page.getByText('Book Information')).toBeVisible();
        await expect(page.getByLabel('Book Title')).toBeVisible();
        await expect(page.getByRole('button', { name: 'Create Book' })).toBeVisible();
    });

    test('can create a book with an author', async () => {
        await page.goto('/library/authors/create');
        await page.getByLabel('First Name').fill('Ernest');
        await page.getByLabel('Last Name').fill('Hemingway');
        await page.getByRole('button', { name: 'Create Author' }).click();
        await page.waitForURL(/\/library\/authors/);
        await page.waitForLoadState('networkidle');

        await page.goto('/library/books/create', { waitUntil: 'load' });
        await page.getByLabel('Book Title').fill('The Old Man and the Sea');

        await page.getByRole('textbox', { name: 'Search Available Authors' }).fill('Hemingway');
        await page.getByRole('textbox', { name: 'Search Available Authors' }).press('Enter');
        await page.waitForTimeout(1000);

        await page.getByRole('button', { name: 'Select author' }).first().click();

        await expect(page.getByText('Ernest Hemingway').first()).toBeVisible();

        await page.getByRole('button', { name: 'Create Book' }).click();
        await page.waitForURL(/\/library\/books/);
    });
});
