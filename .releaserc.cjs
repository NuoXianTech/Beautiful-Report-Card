const isPrerelease = process.env.RELEASE_MODE === 'prerelease';
const defaultReleaseBranch = 'main';
const releaseBranch = process.env.RELEASE_BRANCH || defaultReleaseBranch;

const branches = isPrerelease
  ? releaseBranch === defaultReleaseBranch
    ? [defaultReleaseBranch]
    : [defaultReleaseBranch, {name: releaseBranch, prerelease: 'beta'}]
  : [releaseBranch];

module.exports = {
  branches,
  tagFormat: 'v${version}',
  plugins: [
    '@semantic-release/commit-analyzer',
    '@semantic-release/release-notes-generator',
    '@semantic-release/github',
  ],
};
